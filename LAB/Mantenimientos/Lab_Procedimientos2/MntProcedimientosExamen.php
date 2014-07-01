<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
include("clsLab_Procedimientos.php");
 
$objdatos=new clsLab_Procedimientos;
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head> 
<title>Mantenimiento de Procedimientos de Examen</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Procedimientos.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>

<script language="JavaScript" >
function Guardar(){
   	IngresarRegistro();
}
function LlenarComboExamen(idArea)
{
  LlenarExamenes(idArea);
}

function Buscar(){
	BuscarDatos();
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostraFormularioNuevo();
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
            <div  id="divFrmNuevo" >
                <form id="frmnuevo">
                    <table width="55%" border="0" align="center" class="StormyWeatherFormTABLE">
                        <tr>
                            <td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Procedimientos de Ex&aacute;menes de Laboratorio</h3></strong>
                            </td>
			</tr>	
			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
                            <td width="83%" class="StormyWeatherDataTD">
                                <select id="cmbArea" name="cmbArea" size="1" onChange="LlenarComboExamen(this.value);">
                                    <option value="0" >--Seleccione un &Aacute;rea--</option>
                                        <?php
                                            $objeareas=new clsLab_Areas;
                                            $consulta= $objeareas->consultaractivas($lugar);
                                            while($row = mysql_fetch_array($consulta)){
                                                echo "<option value='" . $row['IdArea']. "'>" . $row['NombreArea'] . "</option>";
                                            }
					?>		  
           			</select>		  
                            </td>
    			</tr>
   			<tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <div id="divExamen">
                                    <select name="cmbExamen" id="cmbExamen" style="width:250px"> 
                                        <option value="0">--Seleccione un Examen--</option>
                                    </select>
				</div>
                            </td>
        		</tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Sexo</td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <select id="cmbSexo" name="cmbSexo" size="1" >
                                    <option value="0" >--Seleccione Sexo--</option>
                                        <?php
                                            $consultaS= $objdatos->consultarsexo();
                                            while($row =mysql_fetch_array($consultaS)){
                                                echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                            }
                                        ?>        
                                </select>		  
			    </td>        
                       </tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Rango Edad</td>
                            <td width="83%"  class="StormyWeatherDataTD">
                                <select id="cmbEdad" name="cmbEdad" size="1" >
                                    <option value="0" >--Seleccione un Rango de Edad--</option>
                                    <?php
                                        $conEdad = $objdatos->RangosEdades();
                                        while($row = mysql_fetch_array($conEdad)){
                                            echo "<option value='" . $row[0]. "'>". $row[1] . "</option>";
                                        }
                                    ?>    
                                </select>		  
                            </td>        
                       </tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Procedimiento</td>
                            <td width="83%" class="StormyWeatherDataTD"><input name="txtproc" type="text" id="txtproc" size="50"></td>
                       </tr>
                       <tr>
                            <td width="17%" class="StormyWeatherFieldCaptionTD">Unidades</td>
                            <td width="83%" class="StormyWeatherDataTD"><input name="txtunidades" type="text" id="txtunidades" size="10"></td>
			</tr>
                        <tr>
                            <td colspan="2" class="StormyWeatherDataTD" >
                                <fieldset><legend><span>Rangos</span></legend>
                                    <table width="200" border="0" align="center" class="StormyWeatherFormTABLE">
                                        <tr>
                                            <td class="StormyWeatherFieldCaptionTD">Inicio</td>
                                            <td class="StormyWeatherDataTD"><input name="txtrangoini" type="text" id="txtrangoini" size="8" ></td>
                                            <td class="StormyWeatherFieldCaptionTD" >Fin </td>
                                            <td class="StormyWeatherDataTD"><input name="txtrangofin" type="text" id="txtrangofin" size="8" ></td>
					</tr>
                                    </table>
				</fieldset>               
                            </td>
			</tr>
			<tr>
                            <td colspan="2" class="StormyWeatherDataTD">
                                <table width="850" border="0" align="center" class="StormyWeatherFormTABLE">
                                    <tr>
                                        <td width="15%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</td>
					<td width="30%" class="StormyWeatherDataTD">
                                            <input name="txtFechainicio" type="text" id="txtFechainicio" size="8" ><input name="button" type="button" id="trigger"  value="...">dd/mm/aaaa
					</td>
					<td width="15%" class="StormyWeatherFieldCaptionTD">Fecha Final</td>
					<td width="30%" class="StormyWeatherDataTD">
                                            <input name="txtFechaFin" type="text" id="txtFechaFin" size="8" ><input name="button2" type="button" id="trigger2" value="...">dd/mm/aaaa
					</td>
                                    </tr>	
				</table>
                            </td>				
			</tr>
			<tr>
                            <td class="StormyWeatherDataTD" colspan="2" align="right">
                                <input type="button" name="btnGuardar" value="Insertar" onClick="Guardar() ;">
				<input type="button" name="btnBuscar" value="Buscar" Onclick="Buscar() ;">
				<input type="button" name="Submit" value="Cancelar" onClick="window.location.replace('MntProcedimientosExamen.php')">			
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
