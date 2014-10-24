<?php session_start();
if(isset($_SESSION['Correlativo'])){
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
include_once("clsSolicitudesPorServicioPeriodo.php"); 
//consulta los datos por su id
$obj = new clsSolicitudesPorServicioPeriodo;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];
 ?>
<html>
<head>
<title>Consulta Solicitudes Por Sub-Servicio</title>
<!--<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />-->
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorServicioPeriodo.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">

<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />

<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript">
function MostrarBusqueda()
{
	if ((document.getElementById('txtfechainicio').value == "") || (document.getElementById('txtfechafin').value == ""))
	{
		alert("¡Complete el rango de las fechas!");
	}
	else 
		BuscarDatos(1);
}

function BuscarMedicos(idsubservicio){
    
        
 	LlenarComboMedico(idsubservicio);
       
}

function BuscarEstablecimiento(idtipoesta){

	LlenarComboEstablecimiento(idtipoesta);
}

function BuscarServicio(IdServicio){
	
	LlenarComboServicio(IdServicio);
}

</script>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" >

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

<div  id="divInicial" >
<form>
<p>&nbsp;</p>
	<table align="center"  class="StormyWeatherFormTABLE" width="85%">
		<tr>
			<td colspan="5" align="center" class="CobaltFieldCaptionTD">
				<h3><strong>Consulta de Solicitudes por Sub-Servicio</strong></h3>
			</td>
		</tr>
		<tr>		
			<td class="StormyWeatherFieldCaptionTD" width="15%">Tipo Establecimiento</td>
			<td class="StormyWeatherDataTD" width="18%">
				<select name="cmbTipoEstab" id="cmbTipoEstab" style="width:375px" onChange="BuscarEstablecimiento(this.value)">
        				<option value="0" >Seleccione un Tipo de Establecimiento</option>
					<?php
				$db = new ConexionBD;
				if($db->conectar()==true){
					$consulta  = "SELECT id,nombre FROM ctl_tipo_establecimiento ORDER BY nombre";
					$resultado = pg_query($consulta) or die('La consulta fall&oacute;: ' . pg_error());
					//por cada registro encontrado en la tabla me genera un <option>
					while ($rows = pg_fetch_array($resultado)){
						echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
					}
						echo '<option value="'. $tipo .'" selected="selected">' .htmlentities($nomtipo). '</option>';
				}
			?>
        			</select>
			</td>
        	<td class="StormyWeatherFieldCaptionTD" width="15%">Establecimiento</td>
        	<td class="StormyWeatherDataTD" width="18%" >
				<div id="divEstablecimiento">
					<select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:375px">
						<option value="0" >Seleccione un Establecimiento</option>
							<?php 
				  echo '<option value="'. $lugar .'" selected="selected">' .htmlentities($nombrEstab). '</option>';
		              	include_once("../../../Conexion/ConexionBD.php");
					$con = new ConexionBD;
					if($con->conectar()==true){			  
						//$consulta  = "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$tipo' ORDER BY Nombre";
                                                $consulta  = "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento='$tipo' ORDER BY nombre";
						$resultado = @pg_query($consulta) or die('La consulta fall&oacute;: ' . @pg_error());
						//por cada registro encontrado en la tabla me genera un <option>
						while ($rows = @pg_fetch_array($resultado)){
							echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]). '</option>';
						}
		            }
				?>		
						</select>
			    </div>
	        </td>
        </tr>
		<tr>	
			<td class="StormyWeatherFieldCaptionTD" width="15%">Procedencia</td>
			<td class="StormyWeatherDataTD" width="18%">
				<select name="CmbServicio" id="CmbServicio" style="width:375px" onChange="BuscarServicio(this.value)" >
					<option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
					<?php
							$db = new ConexionBD;
							if($db->conectar()==true){
								$consulta  = /*"SELECT mnt_servicio.IdServicio,mnt_servicio.NombreServicio FROM mnt_servicio 
								INNER JOIN mnt_servicioxestablecimiento 
								ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
								WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' AND IdEstablecimiento=$lugar";*/
                                                                        "SELECT t01.id,
                                                                 t01.nombre
                                                          FROM ctl_area_atencion t01
                                                          WHERE t01.id IN (
                                                                SELECT DISTINCT id_area_atencion 
                                                                FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)";
                                                                        
								$resultado = pg_query($consulta) or die('La consulta fall&oacute;: '. pg_error());
													
										//por cada registro encontrado en la tabla me genera un <option>
										while ($rows = pg_fetch_array($resultado)){
											echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
										}
							}
						?>
                                                
                                                
                                              
                </select>
			</td>
			<td class="StormyWeatherFieldCaptionTD" width="15%">Sub-Servicio</td>
			<td class="StormyWeatherDataTD" width="18%">
				<div id="divsubserv">
					<select name="cmbSubServ" id="cmbSubServ" onChange="BuscarMedicos(this.value)" style="width:250px">
						<option value="0" selected="selected"> Seleccione un Servicio </option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" width="15%" >Medicos</td>
			<td  class="StormyWeatherDataTD"  width="18%" colspan="3">
				<div id="divMedico">
					<select name="cboMedicos" id="cboMedicos" class="MailboxSelect" style="width:250px"> 
						<option value="0">--Seleccione Un  Medico--</option>
					</select>
				</div>
			</td> 
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD" width="15%">Fecha Inicio </td>
			<td  class="StormyWeatherDataTD" width="18%">
				<input type="text" name="txtfechainicio" id="txtfechainicio" />
				<input name="button" type="button" id="trigger"  value="...">dd/mm/aaaa</td>
			<td class="StormyWeatherFieldCaptionTD" width="15%">Fecha Final </td>
			<td class="StormyWeatherDataTD" width="18%"><input type="text" name="txtfechafin" id="txtfechafin" />
				<input name="button2" type="button" id="trigger2" value="...">dd/mm/dddd</td>
		</tr>
		<tr>
			<td class="StormyWeatherDataTD" colspan="5" align="right"><input type="button" id="btnbuscar" value="Buscar" onClick="MostrarBusqueda();">
				<input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('SolicitudPorServicioPeriodo.php')">
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	Calendar.setup(
	{
	    inputField  : "txtfechainicio",         // el ID texto 
	    ifFormat    : "%d/%m/%Y",    // formato de la fecha
	    button      : "trigger"       // el ID del boton			  	  
	}
	);
	Calendar.setup(
	{
	      inputField  : "txtfechafin",         // el ID texto 
	      ifFormat    : "%d/%m/%Y",    // formato de la fecha
	      button      : "trigger2"       // el ID del boton			  	  
	}
	);
</script>
</div>
<div id="divBusqueda">

</div>
</body>
</html>
<?php
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>
