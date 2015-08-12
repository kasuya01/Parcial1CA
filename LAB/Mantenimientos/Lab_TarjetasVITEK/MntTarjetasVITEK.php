<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
?>
?>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
	<title>Mantenimiento de Tarjetas VITEK</title>
	<script language="JavaScript" type="text/javascript" src="ajax_Lab_TarjetasVITEK.js"></script>

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


	<script language="JavaScript" type="text/javascript">
		function ActualizarDatos()
		{
			enviarDatos();
		}
		function Limpiar()
		{  LimpiarContoles();
		}
                
                function buscartarje()
                {
                    if ((document.getElementById('txtnombretarjeta').value == "") 
                        &&(document.getElementById('txtFechainicio').value == "")
                        &&(document.getElementById('txtFechaFin').value == ""))
                        {  
                            alert("Debe de Ingresar un Parámetro de Búsqueda ");
                        }
                    else{
                        BuscarCodigo();
                        
                    }
                    
             
                    // && (document.getElementById('txtnombretarjeta').value == "")
             
             
                   /* nombretarjeta = document.frmnuevo.txtnombretarjeta.value;
                    Fechaini = document.frmnuevo.txtFechainicio.value;
                    Fechafin = document.frmnuevo.txtFechaFin.value;*/
                 
                }
                
	</script>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">
	<?php 
	if ($nivel==1) {
		include_once ('../../../PaginaPrincipal/index_laboratorio2.php');
	}
	if ($nivel==2) {
		include_once ('../../../PaginaPrincipal/index_laboratorio22.php');
	}
	if ($nivel==31) {
		include_once ('../../../PaginaPrincipal/index_laboratorio31.php');
	}
	if ($nivel==33) {
		include_once ('../../../PaginaPrincipal/index_laboratorio33.php');
	}
        if ($nivel == 5) {
            include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
        if ($nivel == 6) {
            include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
        if ($nivel == 7) {
            include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); } 
	?>
	<br>
	<table align="center" width="100%">
		<tr>
			<td>
				<div  id="divFrmNuevo" >
					<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
						<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
							<tr>
								<td width="20%" colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Tarjetas VITEK</strong></h3></td>
							</tr>	
							<!--tr>
					        	<td width="20%"  class="StormyWeatherFieldCaptionTD">IdTarjeta</td>
					        	<td class="StormyWeatherDataTD"><input type="hidden" name="txtidtarjeta" id="txtidtarjeta" disabled="disabled" /></td>
					        </tr-->
					        <tr>
					        	<td width="20%" class="StormyWeatherFieldCaptionTD">Nombre Tarjeta</td>
					        	<td class="StormyWeatherDataTD"><input type="text" name="txtnombretarjeta" id="txtnombretarjeta" class="form-control" placeholder="Ingrese Nombre de la Tarjeta"/></td>
					        	<input type="hidden" name="txtidtarjeta" id="txtidtarjeta" disabled="disabled" />
					        </tr>
					        <tr>	
					        	<td colspan="2" class="StormyWeatherDataTD">
					        		<table width="100%" border="0" align="center" class="StormyWeatherFormTABLE">
					        			
                                                                 <tr>   
                                                                     <td width="21%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
                                                                     <td class="StormyWeatherDataTD">
                                                                            <input name="txtFechainicio" type="text" id="txtFechainicio" size="25" style="width:269px" placeholder="aaaa-mm-dd"  class="date form-control height placeholder">
                                                                    </td>
                                                                    
                                                                    
                                                                    
                                                                     <td class="StormyWeatherFieldCaptionTD"  width="15%">Fecha Fin</td>
                                                                     <td class="StormyWeatherDataTD" width="20%">
                                                                            <input name="txtFechaFin" type="text" id="txtFechaFin" size="28"   style="width:260px" placeholder="aaaa-mm-dd"  class="date form-control height placeholder">
                                                                    </td>
                                                                 </tr>   
                                                                    
                                                                  <!--  <tr>
					        				<td width="20%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
					        				<td width="30%" class="StormyWeatherDataTD"><input name="txtFechainicio" type="text" id="txtFechainicio" size="6" ><input name="button" type="button" id="trigger"  value="...">dd/mm/aaaa</td>
					        				<td width="20%" class="StormyWeatherFieldCaptionTD">Fecha Final</td>
					        				<td width="30%" class="StormyWeatherDataTD"><input name="txtFechaFin" type="text" id="txtFechaFin" size="6" ><input name="button2" type="button" id="trigger2" value="...">dd/mm/aaaa</td>	
					        			</tr> -->
					        		</table>
					        	</td>			
					        </tr>
					        <!--<tr>
					        	<td class="StormyWeatherDataTD" colspan="2" align="right">
					        		<input type="submit" name="Submit" value="Guardar"> 
					        		<input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
					        		<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntTarjetasVITEK.php')">
					        	</td>
                                                  </tr>  -->   
                                                      <tr>  
                                                        <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                                                <button type='submit' align="center" class='btn btn-primary' id='buscarsolicitud' ><span class='glyphicon glyphicon-floppy-disk'> </span> Guardar</button>
                                                                <button type='button' align="center" class='btn btn-primary' id='buscarsolicitud' onclick='buscartarje(); '><span class='glyphicon glyphicon-search'></span> Buscar </button>
                                                                <button type='button' align="center" class='btn btn-primary' id='nuevabusqueda' onclick="window.location.replace('MntTarjetasVITEK.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                
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
	<!--<script type="text/javascript">
		Calendar.setup({
			inputField  : "txtFechainicio",         // el ID texto 
			ifFormat    : "%d/%m/%Y",    // formato de la fecha
			button      : "trigger"       // el ID del boton			  	  
		});
		Calendar.setup({
			inputField  : "txtFechaFin",         // el ID texto 
			ifFormat    : "%d/%m/%Y",    // formato de la fecha
			button      : "trigger2"       // el ID del boton			  	  
		});
	</script> -->
</body>
</html>
