<?php session_start();
 if(isset($_SESSION['Correlativo'])){
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
 include_once("clsSolicitudesPorPaciente.php"); 
//consulta los datos por su id
$obj = new clsSolicitudesPorPaciente;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];
 ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Solicitudes Por Paciente</title>
<?php include_once $ROOT_PATH."/public/js.php";?>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorPaciente.js"></script>
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
{ IdServ=document.getElementById('CmbServicio').value;
    
	 if ((document.getElementById('txtexpediente').value == "") 
            &&(document.getElementById('PrimerNombre').value == "")
            &&(document.getElementById('SegundoNombre').value == "")
            &&(document.getElementById('PrimerApellido').value == "")
            &&(document.getElementById('SegundoApellido').value == "")
            &&(document.getElementById('txtfecharecep').value == 0)
            && (document.getElementById('cmbEstablecimiento').value == 0) 
            && (document.getElementById('CmbServicio').value == 0) 
            && (document.getElementById('cmbSubServ').value == 0) ) {
	   alert("Ingrese un parmetro de busqueda");
	  // document.getElementById('txtexpediente').focus();
          //txtfecharecep
	   }
    	 /*else{
	     BuscarDatospaciente(1); 
             //alert ("si"+IdServ);
         }*/
        else {
                        jQuery('#divBusqueda').empty();
                        jQuery('#divBusqueda').append('<center><img id="wait" src="<?php echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" style="color:#888888;font-weight: bold;">Buscando...</div></center>');
                        
                        setTimeout(function() {
                            jQuery('#divBusqueda').empty();
                            BuscarDatospaciente(1);
                        }, 500);
                    }
}

function ImprimirExamenes()
{
	imprimiretiquetas();
}

function BuscarEstablecimiento(idtipoesta){

	LlenarComboEstablecimiento(idtipoesta);
	

}
function BuscarServicio(IdServicio){
	
		LlenarComboServicio(IdServicio);
	
	
}
function BuscarExamen(idarea){

	if (document.getElementById('cmbArea').value == 0){
		  alert("Debe Seleccionar una Area");
 
	} 
	else{
		LlenarComboExamen(idarea);
	
	}
}
</script>


<style type="text/css">
<!--
@media print{
#boton{display:none;}
#divInicial{display:none;}
#divInicial{display:none;}
}

-->
</style>
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad=" document.getElementById('txtexpediente').focus() ">

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
<table align="center" width="81%">
<tr>
	<td>
		<div  id="divInicial" >
		<form>
			<p>&nbsp;</p>
			<table align="center"  class="StormyWeatherFormTABLE">
			<tr>
				<td colspan="5" align="center" class="CobaltFieldCaptionTD">
				    <h3><strong>Consulta de Solicitudes por Paciente</strong></h3>
				</td>
			</tr>
			<tr>		
				<td class="StormyWeatherFieldCaptionTD">Tipo Establecimiento</td>
		<td class="StormyWeatherDataTD"><select name="cmbTipoEstab" id="cmbTipoEstab" style="width:405px" onChange="BuscarEstablecimiento(this.value)">
        	<option value="0">Seleccione un Tipo de Establecimiento</option>
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
        	<td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
        	<td class="StormyWeatherDataTD" >
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
		<td class="StormyWeatherFieldCaptionTD">Procedencia</td>
		<td class="StormyWeatherDataTD">
			<select name="CmbServicio" id="CmbServicio" style="width:355px" onChange="BuscarServicio(this.value)" >
				<option value="0" selected="selected" align="center"> Seleccione Procedencia </option>
				<?php
					$db = new ConexionBD;
					if($db->conectar()==true){
						
							
                                            $consulta  = "SELECT t01.id,
                                                                 t01.nombre
                                                          FROM ctl_area_atencion t01
                                                          WHERE t01.id IN (
                                                                SELECT DISTINCT id_area_atencion 
                                                                FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)";
                                            
                                           /* "SELECT mse.id,mse.nombre 
						FROM mnt_servicio_externo mse 
						INNER JOIN mnt_servicio_externo_establecimiento msee 
						ON mse.id=msee.id
						WHERE   msee.id_establecimiento=$lugar";*/
                                            
						$resultado = pg_query($consulta) or die('La consulta fall&oacute;: ' . pg_error());
						//por cada registro encontrado en la tabla me genera un <option>
						while ($rows = pg_fetch_array($resultado)){
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
					<option value="0" selected="selected"> Seleccione un Servicio </option>
				</select>
			</div>
		</td>
	</tr>

	<tr>
		
		<td class="StormyWeatherFieldCaptionTD"  >Expediente</td>
		<td  class="StormyWeatherDataTD" width="5%" ><input type="text" size="24" name="txtexpediente" id="txtexpediente" />
		</td>
		<td class="StormyWeatherFieldCaptionTD" width="19%">Fecha Recepi&oacute;n</td>
		<td  class="StormyWeatherDataTD" width="20%" ><input type="text" size="15" name="txtfecharecep" id="txtfecharecep" />
			<input type="button" value="..." id="trigger">dd/mm/aaaa
		</td>
	</tr>
	<tr>
		<td  class="StormyWeatherFieldCaptionTD" align="left"><strong>Primer Nombre&nbsp;</strong>   </td> 
		<td class="StormyWeatherDataTD" >
			<input class="MailboxInput" maxlength="35" size="28" name="PrimerNombre" id="PrimerNombre"></td> 
		<td class="StormyWeatherFieldCaptionTD" align="left"><strong>Segundo Nombre</strong>   </td> <td class="StormyWeatherDataTD">
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
				<td  class="StormyWeatherDataTD" colspan="4" align="right">
					<input type="button" id="btnbuscar" value="Buscar Solicitudes" onClick="MostrarBusqueda();">
					<input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('SolicitudPorPaciente.php')">
				</td>
			</tr>
			</table>
		</form>
		<script type="text/javascript">
				Calendar.setup(
				{
				inputField  : "txtfechaconsulta",         // el ID texto 
				ifFormat    : "%d/%m/%Y",    // formato de la fecha
				button      : "trigger"       // el ID del boton			  	  
				}
				);
				
		</script>
		</div>
		<div id="divBusqueda">
		
		</div>
		<div id="divSolicitud">
		
		</div>

</body>
</html>
<?php
}else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>