<?php session_start();
if(isset($_SESSION['Correlativo'])){
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
 
 include_once("clsMuestrasRechazadas.php"); 
//consulta los datos por su id
$obj = new clsMuestrasRechazadas;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = pg_fetch_array($consulta);

$ConArea=$obj->DatosArea($area);
$rowArea = pg_fetch_array($ConArea);
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
?>
<html>
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Reactivar Muestras Rechazadas</title>
<script language="JavaScript" type="text/javascript" src="ajax_MuestrasRechazadas.js"></script>
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script language="JavaScript" type="text/javascript">

function MostrarMuestrasRechazadas()

{
	if ((document.getElementById('cmbArea').value == 0) &&(document.getElementById('txtexpediente').value == "") 
		&& (document.getElementById('txtfecharecep').value == "") && (document.getElementById('cmbTipoEstab').value == 0) 
		&& (document.getElementById('cmbEstablecimiento').value == 0) && (document.getElementById('CmbServicio').value == 0) 
		&& (document.getElementById('cmbSubServ').value == 0) && (document.getElementById('PrimerNombre').value=="") 
		&& (document.getElementById('SegundoNombre').value=="") && (document.geElementById('PrimerApellido').value=="") 
		&& (document.getElementById('SegundoApellido').value=="") && (document.getElementById('cmbTipoSolic').value == 0)){
			alert("Ingrese un parametro de busqueda");
	 }
    else if (document.getElementById('cmbArea').value == 0){
				alert ("Debe de ingresar un Área");
	}
	   else {
                        jQuery('#divBusqueda').empty();
                        jQuery('#divBusqueda').append('<center><img id="wait" src="<?php echo $base_url; ?>/Laboratorio/public/images/spin.gif" alt="wait" width="24" height="24"><div id="search-message" style="color:#888888;font-weight: bold;">Buscando...</div></center>');
                        
                        setTimeout(function() {
                            jQuery('#divBusqueda').empty();
                            MuestrasRechazadas();
                        }, 500);
                    }
		
	
   
}

function BuscarExamen(idarea){

	if (document.getElementById('cmbArea').value == 0){
		  alert("Debe Seleccionar una Área");
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
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7">

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
<table align="center" width="85%">

<tr>
<td>

<div  id="divInicial">
<form>
<table  align="center" class="StormyWeatherFormTABLE"  width="97%">
	<tr>
		<td colspan="4" align="center" class="CobaltFieldCaptionTD"><h3><strong>Reactivar Muestras rechazadas</strong></h3>
		</td>
	</tr>
	<tr>		
		<td class="StormyWeatherFieldCaptionTD" width="20%">Tipo Establecimiento</td>
		<td class="StormyWeatherDataTD"  width="35%"><select name="cmbTipoEstab" id="cmbTipoEstab" style="width:405px" onChange="BuscarEstablecimiento(this.value)">
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
        	<td class="StormyWeatherFieldCaptionTD"  width="20%">Establecimiento</td>
        	<td class="StormyWeatherDataTD"  width="35%">
				<div id="divEstablecimiento">
					<select name="cmbEstablecimiento" id="cmbEstablecimiento"  style="width:375px">
						<option value="0" >Seleccione un Establecimiento</option>
				<?php 
				  echo '<option value="'. $lugar .'" selected="selected">' .htmlentities($nombrEstab). '</option>';
		              	include_once("../../../Conexion/ConexionBD.php");
					$con = new ConexionBD;
					if($con->conectar()==true){			  
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
			<select name="CmbServicio" id="CmbServicio" style="width:405px" onChange="BuscarServicio(this.value)" >
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
		<td class="StormyWeatherFieldCaptionTD" width="22%" style="font-weight:bold">
			<b>&Aacute;rea de Laboratorio*</b>
		</td>
		<td class="StormyWeatherDataTD" colspan="1" width="11%">
			<select id="cmbArea" name="cmbArea" size="1" style="width:405px" onChange="BuscarExamen(this.value)">
                            <!--<option value="0" >--Seleccione un &Aacute;rea--</option>-->
				<?php
				include('../../../../Laboratorio/LAB/Mantenimientos/Lab_Areas/clsLab_Areas.php');
				$objeareas=new clsLab_Areas;
				$consulta= $objeareas->consultaractivas($lugar);
				while($row = pg_fetch_array($consulta)){
			        echo "<option value='" . $row['idarea']. "'>" . htmlentities($row['nombrearea']) . "</option>";
				}
				echo '<option value="'.$area1.'" selected="selected">'.htmlentities("--Seleccione Área--").'</option>';
				?>			  
			</select> 
		</td>
		<td  class="StormyWeatherFieldCaptionTD">Examen </td>
   		<td  class="StormyWeatherDataTD"  style="width:205px">
			<div id="divExamen">
				<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:375px"> 
					<option value="0"> Seleccione Examen </option>
				</select>
			</div>
		</td> 
		
	</tr>
	<tr>
		
		<td class="StormyWeatherFieldCaptionTD"  >Expediente</td>
		<td  class="StormyWeatherDataTD" width="5%" ><input type="text" size="28" name="txtexpediente" id="txtexpediente" />
		</td>
		<td class="StormyWeatherFieldCaptionTD" width="19%">Fecha Recepi&oacute;n</td>
		<td  class="StormyWeatherDataTD" width="20%" ><input type="text" size="28" name="txtfecharecep" id="txtfecharecep" class="date"  placeholder="aaaa-mm-dd" />
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
		<td class="StormyWeatherFieldCaptionTD" align="left" colspan="1" align="right">Tipo Solicitud</td>
		<td class="StormyWeatherDataTD" colspan="3">
			<select id="cmbTipoSolic" name="cmbTipoSolic" size="1" >
				<option value="0">Seleccione un Tipo de Solicitud</option>
				<option value="1">URGENTE</option>
				<option value="2">NORMAL</option>
			</select>
		</td>	
	</tr>
	<tr>
		<td  class="StormyWeatherDataTD" colspan="4" align="right">
			<input type="button" name="Submit" value="Buscar Solicitudes" onClick="MostrarMuestrasRechazadas()">
			<input type="button" id="btnClear" value="Nueva Busqueda" class="MailboxButton" onClick="window.location.replace('Proc_MuestrasRechazadas.php')">
		</td>
	<tr>
</table>
</form>
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