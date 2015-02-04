<?php session_start();
include("clsLab_DatosFijosExamen.php");
$objdatos=new clsLab_DatosFijosExamen;
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
<script language="JavaScript" type="text/javascript" src="ajax_Lab_DatosFijosExamen.js"></script>
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
   var cmbarea=document.getElementById('cmbArea').value;
   var cmbexamen=document.getElementById('cmbExamen').value;
   var txtfinicio=document.getElementById('txtFechainicio').value;
   var sexo=document.getElementById('cmbSexo').value;
   var edad=document.getElementById('cmbEdad').value;
   if (cmbarea == 0 || cmbexamen == 0 ||  txtfinicio == "" || sexo == 0 || edad == 0)	
	alert("Ingrese los datos");
   else
   	IngresarRegistro();
}
function LlenarComboExamen(idarea)
{
    //alert("aqui"+idarea);
  LlenarExamenes(idarea);
}

function Buscar(){
 if ((document.getElementById('cmbArea').value == 0) 
        && (document.getElementById('cmbExamen').value == 0) 
        && (document.getElementById('cmbSexo').value == 0)
         && (document.getElementById('cmbEdad').value == 0)
         && (document.getElementById('txtunidades').value == "")
         && (document.getElementById('txtrangoinicio').value == "")
         && (document.getElementById('txtrangofin').value == "")
         && (document.getElementById('txtnota').value == "")
         && (document.getElementById('txtFechainicio').value == "")
         && (document.getElementById('txtFechaFin').value == ""))
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
                <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                    <tr>
                        <td colspan="4" align="center" class="CobaltFieldCaptionTD">
                            <h3><strong>Datos Fijos de Resultados de Ex&aacute;menes de Laboratorio</strong></h3>
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
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
                        <td class="StormyWeatherDataTD" colspan="3">
                            <div id="divExamen">
                               <select name="cmbExamen" id="cmbExamen" style="width:75%" class="form-control height" onchange="buscaranteriores();"> 
                                    <option value="0">--Seleccione un Examen--</option>
				</select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Sexo</td>
                        <td class="StormyWeatherDataTD" colspan="3">
                            <select id="cmbSexo" name="cmbSexo" size="1" class="form-control height"  style="width:75%" onchange="buscaranteriores();">
                               
                              
                                 
                                 
                                 <option value="0" >--Seleccione Sexo--</option>
                                        <?php
                                            $consultaS= $objdatos->consultarsexo();
                                            while($row =pg_fetch_array($consultaS)){
                                                echo "<option value='" . $row['id']. "'>". $row['nombre'] . "</option>";
                                            }
                                            echo "<option value='3'>Ambos</option>"
                                        ?>    
                                 
                                 
                                 
                             </select>		  
			</td>        
                    </tr>
                    <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Edad</td>
                        <td class="StormyWeatherDataTD" colspan="3">
                           <select id="cmbEdad" name="cmbEdad" size="1" class="form-control height"  style="width:75%" onchange="buscaranteriores();">
                                <option value="0" >--Seleccione un Rango de Edad--</option>
                                <?php
                                    $conEdad = $objdatos->RangosEdades();
                                    while($row = pg_fetch_array($conEdad)){
                                        echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                    }
                                 ?>    
                             </select>		  
			</td>        
                     </tr>
                     <tr>
			<td width="17%" class="StormyWeatherFieldCaptionTD">Unidades</td>
                        <td class="StormyWeatherDataTD" colspan="3">
                           <input name="txtunidades" type="text" id="txtunidades" size="10" style="width:250px" placeholder="Unidades" class="form-control height placeholder" /></td>
                     </tr>
                     <tr>
                        <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Inicial</td>
                        <td class="StormyWeatherDataTD">
                           <input name="txtrangoinicio" type="text" id="txtrangoinicio" size="6"  style="width:250px" placeholder="Ingrese Rango Inicial" class="form-control height placeholder" />		  
			</td>      
                          <td width="17%" class="StormyWeatherFieldCaptionTD" style="width:250px" >Rango Final</td>
                        <td class="StormyWeatherDataTD">
                            <input name="txtrangofin" type="text" id="txtrangofin" size="6" style="width:250px" placeholder="Ingrese Rango Final" class="form-control  height placeholder" />  
			</td>      
                     </tr>
                     
<!--                     <tr>
                        <td colspan="2" class="StormyWeatherDataTD">
                            <fieldset><span><center><h4>Rangos</h4></center></span>
                                <table width="200" border="0" align="center" class="StormyWeatherFormTABLE">
                                    <tr>
                                        <td  class="StormyWeatherFieldCaptionTD">Inicio</td>
                                        <td  class="StormyWeatherDataTD"><input name="txtrangoinicio" type="text" id="txtrangoinicio" size="6" ></td>
                                        <td  class="StormyWeatherFieldCaptionTD">Fin </td>
                                        <td  class="StormyWeatherDataTD"><input name="txtrangofin" type="text" id="txtrangofin" size="6" ></td>
                                    </tr>
                                </table>
                            </fieldset>               
			</td>
                     </tr>-->
                     <tr>
                        <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
                        <td  class="StormyWeatherDataTD" colspan="3">
                            <textarea name="txtnota" cols="65" rows="3" id="txtnota" style="width:98%" class="form-control"></textarea>             
			</td>
                     </tr>
                     <tr>
                        <td class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
                        <td  class="StormyWeatherDataTD">
                            <input  name="txtFechainicio" type="text" id="txtFechainicio" size="25" class="date form-control  height placeholder"  placeholder="aaaa-mm-dd" style="width:250px"  value="<?php echo date("Y-m-d"); ?>"/>		  
			</td>      
                          <td class="StormyWeatherFieldCaptionTD" style="width:250px" >Fecha Final</td>
                        <td  class="StormyWeatherDataTD">
                            <input name="txtFechaFin" type="text" id="txtFechaFin" size="28" class="date form-control height placeholder"  placeholder="aaaa-mm-dd" style="width:250px" />
			</td>      
                     </tr>
                     
                     
<!--                     <tr>	
			<td colspan="4" class="StormyWeatherDataTD">
                            <table width="750" border="0" align="center" class="StormyWeatherFormTABLE">
                                <tr>
                                    <td class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
                                    <td  class="StormyWeatherDataTD">
                                        <input name="txtFechainicio" type="text" id="txtFechainicio" size="6" ><input name="button" type="button" id="trigger"  value="...">dd/mm/aaaa</td>
                                    <td class="StormyWeatherFieldCaptionTD">Fecha Final</td>
                                    <td  class="StormyWeatherDataTD">
					<input name="txtFechaFin" type="text" id="txtFechaFin" size="6" ><input name="button2" type="button" id="trigger2" value="...">dd/mm/aaaa</td>	
				</tr>
                                
                                <tr>   
                                    <td width="20%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
                                    <td class="StormyWeatherDataTD">
                                        <input  name="txtFechainicio" type="text" id="txtFechainicio" size="25" class="date"  placeholder="aaaa-mm-dd">
                                    </td>
                                    <td class="StormyWeatherFieldCaptionTD"  width="15%">Fecha Fin</td>
                                    <td class="StormyWeatherDataTD" width="20%">
                                        <input name="txtFechaFin" type="text" id="txtFechaFin" size="28" class="date"  placeholder="aaaa-mm-dd">
                                    </td>
                                </tr>   
                                
                             </table>
			</td>			
                    </tr>-->
                    <!--<tr>
                        <td class="StormyWeatherDataTD" colspan="2" align="right">
                            <input type="button" name="btnGuardar" value="Guardar" onClick="Guardar() ;">
                            <input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
                            <input type="button" name="btnCancelar" value="Cancelar" Onclick="Cancelar() ;">			
			</td>
                    </tr>-->
                    
                    <tr>  
                            <td class="StormyWeatherDataTD" colspan="4" align="right">
                                <button type='button' align="center" class='btn btn-primary'  onclick='Guardar(); '> <span   class='glyphicon glyphicon-floppy-disk'>    </span> Guardar </button>
                                <button type='button' align="center" class='btn btn-primary'  onclick='Buscar(); '>  <span    class='glyphicon glyphicon-search'>         </span> Buscar </button>
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
		<!--<script type="text/javascript">
			Calendar.setup(
				{
					inputField  : "txtFechainicio",         // el ID texto 
					ifFormat    : "%d/%m/%Y",    // formato de la fecha
					button      : "trigger"       // el ID del boton			  	  
				}
			);
			Calendar.setup(
				{
				inputField  : "txtFechaFin",         // el ID texto 
				ifFormat    : "%d/%m/%Y",    // formato de la fecha
				button      : "trigger2"       // el ID del boton			  	  
				}
			);
		</script> -->

			

</body>
</html>
