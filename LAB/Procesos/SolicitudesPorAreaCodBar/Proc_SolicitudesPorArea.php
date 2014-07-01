<?php session_start();
if(isset($_SESSION['Correlativo'])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
include_once("clsSolicitudesPorArea.php");
//consulta los datos por su id
$obj = new clsSolicitudesPorArea;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = mysql_fetch_array($consulta);

$ConArea=$obj->DatosArea($area);
$rowArea = mysql_fetch_array($ConArea);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];
$tipoarea=$rowArea[1];
if ($tipoarea=='S'){
    $area1="0";
    $nomarea="Seleccione un Área";}
else{ 
	$area1=$area;
	$nomarea=$rowArea[0];}
/***************/

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Recepcion de Examenes en &Aacute;reas de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesPorArea.js"></script>
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>

<script language="JavaScript" type="text/javascript">
function MostrarSolicitudes()
{
	if ((document.getElementById('cmbTipoEstab').value == 0) && (document.getElementById('cmbEstablecimiento').value == 0) 
	&& (document.getElementById('CmbServicio').value == 0) 	&& (document.getElementById('cmbArea').value == 0)
	&& (document.getElementById('txtexpediente').value == "") && (document.getElementById('cmbExamen').value == 0) 
	&& (document.getElementById('PrimerNombre').value == "") && (document.getElementById('SegundoNombre').value == "") 
	&& (document.geElementById('PrimerApellido').value == "") && (document.getElementById('SegundoApellido').value == "") 
	&& (document.getElementById('txtfechasolicitud').value == "") && (document.getElementById('Codigo').value == "")
	&& (document.getElementById('Orden').value =="") && (document.getElementById('cmbTipoSolic') == 0))
	{
			alert("Ingrese parámetros de búsqueda");
	 
	}
    else if (document.getElementById('cmbArea').value == 0) {
			/*if (document.getElementById('Codigo').value =="")*/
				alert("Debe de ingresar un Área");
			/*else
				SolicitudesPorArea();*/
	}
    else
			
			SolicitudesPorArea();
	
	
}


function BuscarExamen(idarea){

  if (document.getElementById('cmbArea').value == 0){
		  alert("Debe Seleccionar una Area");
 
	} 
	else{
		LlenarComboExamen(idarea);
	
	}
}

function BuscarEstablecimiento(idtipoesta){

		LlenarComboEstablecimiento(idtipoesta);
	
	}

function BuscarServicio(IdServicio){
			LlenarComboServicio(IdServicio);
	
	}



</script>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="frmnuevo.Codigo.focus();" >

<?php 

if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
if ($nivel==4){
	include_once ('../../../PaginaPrincipal/index_laboratorio42.php');}            
        
?><br>
<div  id="divInicial" >
    <form name="frmnuevo" id="frmnuevo" action="" onSubmit="MostrarSolicitudes();return false">
        <table  align="center" class="StormyWeatherFormTABLE" width="83%">
            <tr>
                <td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Solicitudes a Procesar por &Aacute;rea</strong></h3></td>
            </tr>
            <tr>
		<td class="StormyWeatherFieldCaptionTD">Codigo</td>
		<td class="StormyWeatherDataTD" colspan="1"><input class="MailboxInput" maxlength="35" size="28" name="Codigo" id="Codigo"></td>
		<td class="StormyWeatherFieldCaptionTD">No. Orden</td>
		<td class="StormyWeatherDataTD" colspan="1"><input class="MailboxInput" maxlength="35" size="28" name="Orden" id="Orden"></td>
            </tr>
            <tr>		
            	<td class="StormyWeatherFieldCaptionTD">Tipo Establecimiento</td>
            	<td class="StormyWeatherDataTD">
                    <select name="cmbTipoEstab" id="cmbTipoEstab" style="width:406px" onChange="BuscarEstablecimiento(this.value)">
                        <option value="0" >Seleccione un Tipo de Establecimiento</option>
			<?php
                            $db = new ConexionBD;
                            if($db->conectar()==true){
                                $consulta  = "SELECT IdTipoEstablecimiento,NombreTipoEstablecimiento FROM mnt_tipoestablecimiento ORDER BY NombreTipoEstablecimiento";
				$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
				//por cada registro encontrado en la tabla me genera un <option>
				while ($rows = mysql_fetch_array($resultado)){
                                    echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
				}
                                    echo '<option value="'. $tipo .'" selected="selected">' .htmlentities($nomtipo). '</option>';
                            }
			?>
                    </select>
		</td>
		<td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
		<td class="StormyWeatherDataTD" >
                    <div id="divEstablecimiento">
                        <select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:375px"> <option value="0" >Seleccione un Establecimiento</option>
                            <?php echo '<option value="'. $lugar .'" selected="selected">' .htmlentities($nombrEstab). '</option>';
		               	 include_once("../../../Conexion/ConexionBD.php");
							$con = new ConexionBD;
							if($con->conectar()==true){			  
					       			$consulta  = "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento where IdTipoEstablecimiento='$tipo' ORDER BY Nombre";
								$resultado = @mysql_query($consulta) or die('La consulta fall&oacute;: ' . @mysql_error());
							//por cada registro encontrado en la tabla me genera un <option>
								while ($rows = @mysql_fetch_array($resultado)){
									echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]). '</option>';
								}
		                                
						//@mysql_free_result($consulta); // Liberar memoria usada por consulta.
							}?>	
							</select>
						</div>
					</td>
		                      
				</tr>
		        <tr>	
					<td class="StormyWeatherFieldCaptionTD">Procedencia</td>
				  	<td class="StormyWeatherDataTD">
						<select name="CmbServicio" id="CmbServicio" style="width:355px" onChange="BuscarServicio(this.value)" >
							<option value="0" selected="selected" align="center">Seleccione Procedencia</option>
								<?php
									$db = new ConexionBD;
									if($db->conectar()==true){
										$consulta  = "SELECT mnt_servicio.IdServicio,mnt_servicio.NombreServicio FROM mnt_servicio 
										INNER JOIN mnt_servicioxestablecimiento 
										ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
										WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' AND IdEstablecimiento=$lugar";
										$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
												
									//por cada registro encontrado en la tabla me genera un <option>
										while ($rows = mysql_fetch_array($resultado)){
											echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
										}
									}
								?>
		                        		</select>
					</td>
					<td class="StormyWeatherFieldCaptionTD">Servicio</td>
				  	<td class="StormyWeatherDataTD">
						<div id="divsubserv">
							<select name="cmbSubServ" id="cmbSubServ" style="width:375px" >
		          					<option value="0" selected="selected" >Seleccione un Servicio</option>
		          				</select>
						</div>
					</td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD"  style="font-weight:bold">
						<b>&Aacute;rea de Laboratorio </b>
					</td>
					<td class="StormyWeatherDataTD" >
						<select id="cmbArea" name="cmbArea" size="1" onChange="BuscarExamen(this.value)" >
							<!--<option value="0">Seleccione un &Aacute;rea</option>-->
							<?php
								include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
								$objeareas=new clsLab_Areas;
								$consulta= $objeareas->consultaractivas($lugar);
								while($row = mysql_fetch_array($consulta)){
									echo "<option value='" . $row['IdArea']. "'>" . htmlentities($row['NombreArea']) . "</option>";
								}
								
								echo '<option value="'.$area1.'" selected="selected">'.htmlentities($nomarea).'</option>';
								//mysql_free_result($consulta);
							?>		  
						</select> 
					</td>
					<td  class="StormyWeatherFieldCaptionTD">Examen </td>
					<td  class="StormyWeatherDataTD" >
						<div id="divExamen">
							<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:375px"> 
								<option value="0">Seleccione Examen</option>
							</select>
						</div>
					</td>
				
		  		</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD" >Expediente</td>
					<td  class="StormyWeatherDataTD" ><input type="text" size="24" name="txtexpediente" id="txtexpediente" /></td>
					<td class="StormyWeatherFieldCaptionTD" width="15%" >Fecha Consulta</td>
					<td  class="StormyWeatherDataTD" width="20%" ><input type="text" size="15" name="txtfechasolicitud" id="txtfechasolicitud"/>
						<input type="button" value="..." id="trigger">dd/mm/aaaa</td> 
				</tr>
				<tr>
					<td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Nombre&nbsp;</strong></td> 
					<td class="StormyWeatherDataTD" >
						<input class="MailboxInput" maxlength="35" size="28" name="PrimerNombre" id="PrimerNombre"></td> 
					<td class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Nombre</strong></td> 
					<td class="StormyWeatherDataTD">
						<input  maxlength="35" size="28" name="SegundoNombre" id="SegundoNombre"></td> 
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Apellido</strong></td> 
					<td class="StormyWeatherDataTD">
						<input class="MailboxInput" maxlength="35" size="28" name="PrimerApellido" id="PrimerApellido"></td> 
					<td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Apellido</strong></td> 
					<td class="StormyWeatherDataTD" >
						<input class="MailboxInput" maxlength="35" size="28" name="SegundoApellido" id="SegundoApellido" ></td>
				</tr>
				<tr>
					<td class="StormyWeatherFieldCaptionTD" align="left" colspan="1" align="right">Tipo Solicitud</td>
					<td class="StormyWeatherDataTD" colspan="3">
						<select id="cmbTipoSolic" name="cmbTipoSolic" size="1" >
							<option value="0">Seleccione un Tipo de Solicitud</option>
							<option value="S">URGENTE</option>
							<option value="R">NORMAL</option>
						</select>
					</td>		
				</tr>
				<tr>
					<td  class="StormyWeatherDataTD" colspan="4" align="right">
						<!--<input type="button" name="Submit" value="Buscar Solicitudes" onClick="MostrarSolicitudes()">-->
						<button style="cursor:pointer;" type="submit" class="fg-button ui-state-default ui-corner-all" align="center">Buscar</button>
						<!--<input type="submit" name="Submit" value="Buscar Solicitudes">-->
						<input style="cursor:pointer;" type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('Proc_SolicitudesPorArea.php')">
					</td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">
				Calendar.setup(
					{
						inputField  : "txtfechasolicitud",         // el ID texto 
						ifFormat    : "%d/%m/%Y",    // formato de la fecha
						button      : "trigger"       // el ID del boton			  	  
					}
				);
		</script>
	</div>
	<div id="divBusqueda">

	</div>
	<div id="divResultado">

	</div>
	<div id="divCambioEstado">
	</div>
	<div id="divImprimir">
	</div>
	</td>
</tr>
</body>
</html>
<?php
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>