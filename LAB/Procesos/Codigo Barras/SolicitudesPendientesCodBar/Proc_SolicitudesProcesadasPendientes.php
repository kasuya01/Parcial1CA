<?php session_start();
if(isset($_SESSION['Correlativo'])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//$area='BAT';
//echo $area; 
include_once("clsSolicitudesProcesadas.php"); 
//consulta los dato
$obj = new clsSolicitudesProcesadas;
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
  $area1=0;
  $nomarea="Seleccione un Area";}
else{ 
	$area1=$area;
	$nomarea=$rowArea[0];}
//echo $area1."***".$nomarea."***".$tipoarea; 
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Solicitudes Procesadas</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>

<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />
<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript">


function MostrarSolicitudesPendientes(){	
var ar;
ar=document.getElementById('cmbArea').value;
if ((ar == 0) && (document.getElementById('txtexpediente').value == "") && 
		(document.getElementById('txtfecharecep').value == "") && (document.getElementById('cmbExamen').value == 0) &&
 		(document.getElementById('cmbTipoEstab').value == 0) && (document.getElementById('cmbEstablecimiento').value == 0) &&
		(document.getElementById('CmbServicio').value == 0) && (document.getElementById('cmbSubServ').value == 0) && 		
		(document.getElementById('PrimerNombre').value=="") && (document.getElementById('SegundoNombre').value=="") && 
		(document.geElementById('PrimerApellido').value=="") && (document.getElementById('SegundoApellido').value=="") &&
		(document.getElementById('Codigo').value=="") && (document.getElementById('Orden').value==""))
	{
		alert("Ingrese un par&aacute;metro de b&uacute;squeda");
	 
	}else{
		if ((ar == 0) && 
			(document.getElementById('txtexpediente').value == "") &&
			(document.getElementById('Codigo').value == "")){
		//if ((document.getElementById('txtnombrearea').value == "") || (document.getElementById('txtnombrearea').value == 0)) {
			alert("Ingrese un parametro de busqueda")
			
			if (document.getElementById('Codigo').value ==""){
				alert("Debe de ingresar un Area o Codigo ");
			}else{
			//alert('dddd'+ar)
				SolicitudesPorAreaPendientes();
				}
		}else{
		//alert('dddd1'+ar)
			SolicitudesPorAreaPendientes();
		}
	}
}

function ActualizarSolicitudes()
{
	alert("Voy actualizar lista");
}

function BuscarEstablecimiento(idtipoesta){

	LlenarComboEstablecimiento(idtipoesta);
	
}

function BuscarExamen(idarea){

  if (document.getElementById('cmbArea').value == 0){
	alert("Debe Seleccionar una Area");
 
 } 
	else{
		LlenarComboExamen(idarea);
	
	}
}

function BuscarServicio(IdServicio){
	
		LlenarComboServicio(IdServicio);
}

</script>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="StormyWeathePageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">

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

<div  id="divInicial" >
	<!--<form  action="" name="frmdatos_resultado" id="frmdatos_resultado" onSubmit="MostrarSolicitudesPendientes();return false">-->
	<form name="frmnuevo" id="frmnuevo" action="" onSubmit="MostrarSolicitudesPendientes();return false">
		<table  align="center"  class="StormyWeatherFormTABLE">
			<tr>
				<td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Solicitudes Pendientes en el &Aacute;rea</strong></h3></td>
			</tr>
			<tr>
				<td class="StormyWeatherFieldCaptionTD">Codigo</td>
				<td class="StormyWeatherDataTD" colspan="1"><input class="MailboxInput" maxlength="35" size="28" name="Codigo" id="Codigo"></td>
				<td class="StormyWeatherFieldCaptionTD">No. Orden</td>
				<td class="StormyWeatherDataTD" colspan="1"><input class="MailboxInput" maxlength="35" size="28" name="Orden" id="Orden"></td>
			</tr>
			<tr>		
				<td class="StormyWeatherFieldCaptionTD">Tipo Establecimiento</td>
				<td class="StormyWeatherDataTD"><select name="cmbTipoEstab" id="cmbTipoEstab" style="width:375px" onChange="BuscarEstablecimiento(this.value)" onclick="BuscarEstablecimiento(this.value)">
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
							<select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:375px"> 
							<option value="0" >Seleccione un Establecimiento</option>
								<?php echo '<option value="'. $lugar .'" selected="selected">' .htmlentities($row[1]). '</option>';
	                                 			include_once("../../../Conexion/ConexionBD.php");
								$con = new ConexionBD;
								if($con->conectar()==true){			  
				       					$consulta  = "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento where IdTipoEstablecimiento='$tipo' ORDER BY Nombre";
									$resultado = @mysql_query($consulta) or die('La consulta fall&oacute;: ' . @mysql_error());
									//por cada registro encontrado en la tabla me genera un <option>
									while ($rows = @mysql_fetch_array($resultado)){
										echo '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]). '</option>';
									}
	                            }
								?>	
						</select>
					</div>
				</td>
	        </tr>
			<tr>	
				<td class="StormyWeatherFieldCaptionTD">Procedencia</td>
			  	<td class="StormyWeatherDataTD">
					<select name="CmbServicio" id="CmbServicio" style="width:375px" onChange="BuscarServicio(this.value)" >
						<option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
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
							}						?>
	                        		</select>
				</td>
				<td class="StormyWeatherFieldCaptionTD">Servicio</td>
			  	<td class="StormyWeatherDataTD">
					<div id="divsubserv">
						<select name="cmbSubServ" id="cmbSubServ" style="width:375px" >
	          					<option value="0" selected="selected"> Seleccione un Servicio </option>
	          				</select>
					</div>
				</td>
			</tr>
	        <tr>                	
				<td class="StormyWeatherFieldCaptionTD">&Aacute;rea de Laboratorio</td>
				<td class="StormyWeatherDataTD">
					<select id="cmbArea" name="cmbArea" size="1" onChange="BuscarExamen(this.value)" >
					<option value="0" >Seleccione un &Aacute;rea</option>
					<?php
						//echo '<option value="'. $area .'" selected="selected">' .htmlentities($nomarea). '</option>';
						include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
						$objeareas=new clsLab_Areas;
						$consulta= $objeareas->consultaractivas($lugar);
						while($row = mysql_fetch_array($consulta)){
							echo "<option value='" . $row['IdArea']. "'>" .$row['NombreArea'] . "</option>";
						}
						echo '<option value="'.$area1.'" selected="selected">'.htmlentities($nomarea).'</option>';
						//mysql_free_result($row);		
					?>		  
					</select> 
				</td>
				<td  class="StormyWeatherFieldCaptionTD">Examen </td>
	   			<td  class="StormyWeatherDataTD"  style="width:205px">
					<div id="divExamen">
						<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px"> 
							<option value="0">--Seleccione Examen--</option>
						</select>
					</div>
				</td> 
			</tr>
			<tr>
				<td class="StormyWeatherFieldCaptionTD" >Expediente</td>
				<td  class="StormyWeatherDataTD"><input type="text" size="18" name="txtexpediente" id="txtexpediente" /></td>
				<td class="StormyWeatherFieldCaptionTD">Fecha Recepi&oacute;n</td>
				<td  class="StormyWeatherDataTD" colspan="3"><input type="text" size="12" name="txtfecharecep" id="txtfecharecep"/><input type="button" value="..." id="trigger">dd/mm/aaaa
				</td>
			</tr>
			<tr>
				<td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Nombre&nbsp;</strong>   </td> 
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
				<td class="StormyWeatherDataTD" colspan="4" align="right">
					<!--<input type="button" name="Submit" value="Buscar Solicitudes" onClick="MostrarSolicitudesPendientes()">-->
					<button style="cursor:pointer;" type="submit" class="fg-button ui-state-default ui-corner-all" align="center">Buscar Solicitudes</button>
					<input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('Proc_SolicitudesProcesadasPendientes.php')">
					
				</td>
			</tr>
		</table><input type="hidden" size="18" name="txtnombrearea" id="txtnombrearea" value="<?php if ($tipoarea=='S'){
  $area1=0;
  $nomarea=0; echo $nomarea;
  }else{
  $area1=$area;
	$nomarea=$rowArea[0];echo $nomarea;
  }
  ?>"/>
	</form>
	<script type="text/javascript">
			Calendar.setup(
				{
				inputField  : "txtfecharecep",         // el ID texto 
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
</body>
</html>
<?php
}
else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>