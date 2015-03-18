<?php session_start();
include("clsLab_metodologia.php");
$objdatos=new clsLab_metodologia();
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
<title>Mantenimiento de Examenes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_metodologia.js"></script>
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>

<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script language="JavaScript" >
    
function Guardar(){
   var cmbexamen=document.getElementById('cmbExamen').value;
   var cmbmetodologia=document.getElementById('cmbMetodologia').value;
   var cmbreporta=document.getElementById('cmbreporta').value;
   var txtfinicio=document.getElementById('txtFechainicio').value;
   if (cmbmetodologia == 0 || cmbexamen == 0 ||  txtfinicio == "" || cmbreporta==0)	
	alert("Ingrese todos los datos requeridos por favor");
   else
   	IngresarRegistro();
}
function LlenarComboExamen(idarea)
{
   $( "#add_posresultado" ).prop( "disabled", true );
   document.getElementById('posresultados_sel').value="";
    //alert("aqui"+idarea);
  LlenarExamenes(idarea);
}

function Buscar(){
 if ((document.getElementById('cmbArea').value == 0) 
        && (document.getElementById('cmbExamen').value == 0) 
        && (document.getElementById('cmbMetodologia').value == 0)
         && (document.getElementById('cmbreporta').value == 0)
         && (document.getElementById('txtFechainicio').value == ""))
 {
    alert("Debe de Ingresar un Parámetro de Búsqueda "); 
 }
	
  else
  {
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
LimpiarCampos();    
show_event(1);
}
function popup(URL) {
        myWindow=window.open(URL, '" + "', 'scrollbars=yes, width=700, height=700, left=100, top = 100');
    }
</script>
<style type="text/css">
</style>
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
        <div id="divFrmNuevo" >
            <form name="frmnuevo">
               <input type="hidden" id="actualiza" name="actualiza" value="0"/>
                <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                    <tr>
                        <td colspan="4" align="center" class="CobaltFieldCaptionTD">
                            <h3><strong>Configuración de metodología</strong></h3>
			</td>
                    </tr>	
                    <tr>
                         <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                         <td class="StormyWeatherDataTD" colspan="3">
                            <select id="cmbArea" name="cmbArea" size="1" style="width:75%" onChange="LlenarComboExamen(this.value);" class="form-control height">
                                <option value="0" >--Seleccione un &Aacute;rea--</option>
				<?php
                                    include('../Lab_Areas/clsLab_Areas.php');
                                    $objeareas=new clsLab_Areas;
                                    $consulta= $objeareas->consultaractivas($lugar);
                                    while($row = pg_fetch_array($consulta)){
                                        echo "<option value='".$row['idarea']."'>".$row['nombrearea']."</option>";
                                    }
				?>		  
                            </select>		  
			</td>
                    </tr>
                    <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Examen * </td>
                        <td class="StormyWeatherDataTD" colspan="3">
                            <div id="divExamen">
                               <select name="cmbExamen" id="cmbExamen" style="width:75%" class="form-control height" onchange="buscaranteriores();"> 
                                    <option value="0">--Seleccione un Examen--</option>
				</select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Metodología *</td>
                        <td class="StormyWeatherDataTD" colspan="3">
                            <div id="divMetodo">
                               <select name="cmbMetodologia" id="cmbExamen" style="width:75%" class="form-control height" onchange="buscareporta();"> 
                                    <option value="0">--Seleccione una Metodología--</option>
				</select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                       
                       <td width="17%" class="StormyWeatherFieldCaptionTD" title="Indicar si se mostrará el nombre de la metodología en el reporte final">Reporta *</td>
                        <td class="StormyWeatherDataTD" colspan="3" title="Indicar si se mostrará el nombre de la metodología en el reporte final">
                           <div id="divReporta">
                              <select id="cmbreporta" name="cmbreporta" size="1" class="form-control height"  style="width:75%" title="Indicar si se mostrará el nombre de la metodología en el reporte final">                                              <option value="0" selected="selected">--Seleccione un opción--</option>
                              <option value="true" >Si</option>
                              <option value="false" >No</option>     
                             </select>	
                           </div>
                            	  
			</td>        
                    </tr>
                    <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Posibles Resultados</td>
                        
                        
                        <td class="StormyWeatherDataTD" colspan="3">
                           <div id="posresultprevios">
                            <input type="hidden" name="posresultados_sel" id="posresultados_sel" value="">
                            <input type="hidden" name="text_posresultados_sel" id="text_posresultados_sel">
                            <input type="hidden" name="id_posresultados_sel" id="id_posresultados_sel">
                           </div>
                            <button type='button' class='btn btn-default' disabled="disabled"  name="add_posresultado" id="add_posresultado" style="width:75%; text-align: left" onclick="popup('consulta_posresultados.php?form=frmnuevo&posresultados_sel='+document.getElementById('posresultados_sel').value+
                                        '&text_posresultados_sel='+document.getElementById('text_posresultados_sel').value+
                                        '&nombre='+$('#cmbMetodologia').text()+ '&id_posresultados_sel='+document.getElementById('id_posresultados_sel').value+'&actualizar='+0) ;"><span class='glyphicon glyphicon-th-list'></span> ..:Seleccionar Posibles Resultados:..</button>
                           
                        </td>      
                     </tr>                    
                     <tr>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Inicio *</td>
                        <td  class="StormyWeatherDataTD">
                            <input  name="txtFechainicio" type="text" id="txtFechainicio" size="25" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd" style="width:100%"  value="<?php echo date("Y-m-d"); ?>"/>		  
			</td>      
                          <td class="StormyWeatherFieldCaptionTD" style="width:250px" >Fecha Final</td>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechaFin" type="text" id="txtFechaFin" size="28" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:100%" />
			</td>      
                     </tr>          
                    <tr>  
                            <td class="StormyWeatherDataTD" colspan="4" align="right"><br/>
                                <button type='button' align="center" class='btn btn-primary'  onclick='Guardar(); '> <span   class='glyphicon glyphicon-floppy-disk'>    </span> Guardar </button>
<!--                                <button type='button' align="center" class='btn btn-primary'  onclick='Buscar(); '>  <span    class='glyphicon glyphicon-search'>         </span> Buscar </button>-->
                                <button type='button' align="center" class='btn btn-primary'  onclick='Cancelar(); '><span  class='glyphicon glyphicon-refresh'>        </span> Nueva Busqueda</button>
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
	<div  id="divinicial" >
            
	</div>
    </td>
</tr>
</table>

</body>
</html>
