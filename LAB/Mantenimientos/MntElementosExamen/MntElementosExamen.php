<?php session_start();
include("clsElementosExamen.php");
$objele=new clsElementosExamen;
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
		<script language="JavaScript" type="text/javascript" src="ajax_ElementosExamen.js"></script>
			<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
			<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
		<!--referencias del estilo del calendario-->
			<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
			<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
		<!--llamado al archivo de funciones del calendario-->
			<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
			<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
			<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
			<?php include_once $ROOT_PATH.'/public/css.php';?>
                        <?php include_once $ROOT_PATH.'/public/js.php';?>
                        <script language="JavaScript" >
			function Guardar(){
				IngresarRegistro();
			}
			function Buscar(){
				BuscarDatos();
			}
			function Actualizar(){
				enviarDatos();
			}
			function Nuevo(){
				MostrarFormularioNuevo();
			}
			function Cancelar()
			{
				LimpiarCampos();
				show_event(1);
			}
                        function llenarcomboRango(idexa){
                            //alert(idexa);
                        LlenarRango(idexa)
    
                        }
		</script>
		<style type="text/css"></style>
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
<table align="center" width="100%" >
    <tr>
        <td>
            <div  id="divFrmNuevo" >
                <form name="frmnuevo">
                    <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
                        <tr>
                            <td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Elementos de  Examen</h3></strong>	</td>
			</tr>	
			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                            <td width="83%" colspan="3" class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea" style="width:50%" onChange="MostrarExamenes(this.value);" class="form-control height">
                                    <option value="0" >--Seleccione un &Aacute;rea--</option>
                                        <?php
                                            include('../Lab_Areas/clsLab_Areas.php');
                                            $objeareas=new clsLab_Areas;
                                            $consulta= $objeareas->consultaractivas($lugar);
                                            while($row = pg_fetch_array($consulta)){
						echo "<option value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
                                            }
                                         ?>		  
				</select>		  
                            </td>
			</tr>
			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
                            <td width="83%" colspan="3" class="StormyWeatherDataTD">
                                <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen" style="width:50%" class="form-control height"> 
                                        <option value="0">--Seleccione un Examen--</option>
                                    </select>
				</div>
                            </td>
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD">Elemento</td>
                            <td class="StormyWeatherDataTD" colspan="3" ><textarea name="txtelemento" cols="75" rows="2" id="txtelemento"  > </textarea> 
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD">Unidad</td>
                            <td class="StormyWeatherDataTD" colspan="3" ><input name="txtunidadele" type="text" id="txtunidadele" value="" size="15" >
                            </td>
			</tr>
			<tr>
                            <td class="StormyWeatherFieldCaptionTD">Observci&oacute;n o Interpretaci&oacute;n</td>
                            <td class="StormyWeatherDataTD" colspan="3" ><textarea id="txtobservacionele" name="txtobservacionele" cols="75" rows="4"></textarea>
			</tr>
			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">SubElementos</td>
                            <td width="83%" class="StormyWeatherDataTD" colspan="3" >
                                <select id="cmbSubElementos" name="cmbSubElementos" style="width:50%" class="form-control height">
                                    <option value="0" >--Seleccione--</option>
                                    <option value="S" >Si</option>
                                    <option value="N" >No</option>
				</select>
                            </td>
			</tr>
			<tr>
<!--                           <td colspan="2" class="StormyWeatherDataTD">
                               <table width="850" border="0" align="center" class="StormyWeatherFormTABLE">
                                   <tr>-->
                                        <td width="17%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
					<td width="35%" class="StormyWeatherDataTD">
                                            <input name="txtFechainicio" type="text" id="txtFechainicio" size="10" ><input name="button" type="button" id="trigger"  value="...">dd/mm/aaaa
					</td>
					<td width="13%" class="StormyWeatherFieldCaptionTD">Fecha Final</td>
					<td width="39%" class="StormyWeatherDataTD">
                                            <input name="txtFechaFin" type="text" id="txtFechaFin" size="10" ><input name="button2" type="button" id="trigger2" value="...">dd/mm/aaaa
					</td>
<!--                                    </tr>	
				</table>
                            </td>				-->
			</tr>
                        <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Orden </td>
                            <td width="83%" colspan="3"  class="StormyWeatherDataTD"> 
                                <div id="divRango">
                                    <select   name="cmborden"  id="cmborden" style="width:50%"  class="form-control height"> 
                                        <option value="0">--Seleccione un Orden--</option>
                                    
                                    </select>
                                </div>
                            </td>
                        </tr>    
			<tr>
                            <td colspan="2" class="StormyWeatherDataTD" align="right">
                                <button type="button" name="Submit" class="btn btn-primary" value="Guardar" onClick="Guardar() ;"><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                 <!--<button type='button' align="center" class='btn btn-primary' id="abrir"  onclick='Guardar(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>-->
				<button type="button" name="Submit2" class="btn btn-primary" value="Buscar" Onclick="javascript:Buscar();"> <span class='glyphicon glyphicon-search'></span>  Buscar </button>
				<button type="button" name="Submit3"  class="btn btn-primary" value="Nuevo" onClick="window.location.replace('MntElementosExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>	

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

            <div  id="divresultado" >
            </div>
        </td>
    </tr>
</table>
	<script type="text/javascript">
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
	</script>
	</body>
</html>
