<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$nivel=$_SESSION['NIVEL'];
?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<link rel="stylesheet" type="text/css" href="../../Webstyle/Themes/Cobalt/Style.css">
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>
<title>Reporte de Solicitudes por Sub-Servicio</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorServicioPeriodo.js"></script>
<script language="JavaScript" >
function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
	{
		if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
               		subservicio = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var2=' )
			fechainicio = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var3=' )
			fechafin = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var4=' )
			medico = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var5=' )
			IdEstab = getVars[i].substr(5);
		if ( getVars[i].substr(0,5) == 'var6=' )
			IdServ = getVars[i].substr(5);
	}

}

function otroreport(){
	window.location.replace('SolicitudPorServicioPeriodo.php');
}

function imprimir(){
document.getElementById('btnImprimir').style.visibility="hidden";
window.print();
document.getElementById('btnImprimir').style.visibility="visible";
}
</script>
</head>

<body onLoad="RecogeValor();">

<?php 
include_once("clsSolicitudesPorServicioPeriodo.php");
$especialidad=$_GET['var1'];
$fechainicio=$_GET['var2'];
$fechafin=$_GET['var3'];
//echo $fechainicio."".$fechafin;
$medico=$_GET['var4'];
$objdatos = new clsSolicitudesPorServicioPeriodo;

 $query = "SELECT  sec_historial_clinico.IdNumeroExp, 
	sec_solicitudestudios.IdSolicitudEstudio,
	DATE_FORMAT(sec_solicitudestudios.FechaSolicitud ,'%e/ %m / %Y') AS FechaSolicitud,
	mnt_subservicio.NombreSubServicio AS origen, mnt_servicio.NombreServicio AS procedencia,
	mnt_empleados.NombreEmpleado AS medico, 
	CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,
	CASE sec_solicitudestudios.Estado 
	WHEN 'D' THEN 'Digitada'
	WHEN 'R' THEN 'Recibida'
	WHEN 'P' THEN 'En Proceso'    
	WHEN 'C' THEN 'Completa' END AS Estado,mnt_establecimiento.Nombre
	FROM sec_historial_clinico 
	INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio 
	INNER JOIN mnt_empleados ON sec_historial_clinico.IdEmpleado= mnt_empleados.IdEmpleado
	INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp= mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente= mnt_datospaciente.IdPaciente
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento= mnt_establecimiento.IdEstablecimiento
	WHERE  sec_solicitudestudios.IdServicio ='DCOLAB' AND";
		$ban=0;
			//VERIFICANDO LOS POST ENVIADOS
	if (!empty($_GET['var5']))
		{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_GET['var5']."' AND";}

	if (!empty($_GET['var1']))
	{ $query .= " sec_historial_clinico.IdSubServicio='".$_GET['var1']."' AND";}
	
	if (!empty($_GET['var4']))
		{ $query .= " sec_historial_clinico.IdEmpleado='".$_GET['var4']."' AND";}
	
	if ((!empty($_GET['var2'])) and (!empty($_GET['var3'])))
	{ $Nfechaini=explode("/",$fechainicio);
	  $Nfechafin=explode("/",$fechafin);
		 	//print_r($Nfecha);
               	$Nfechaini=$Nfechaini[2]."-".$Nfechaini[1]."-".$Nfechaini[0]; 
		$Nfechafin=$Nfechafin[2]."-".$Nfechafin[1]."-".$Nfechafin[0]; 
		$query .= " sec_solicitudestudios.FechaSolicitud BETWEEN '".$Nfechaini."' AND '".$Nfechafin."' ";}
  
  
        if((empty($_GET['var1'])) and (empty($_POST['var4'])) and (empty($_GET['var2'])) and (empty($_GET['var3'])) and (empty($_GET['var5'])))
		{
				$ban=1;
		}
			
	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-1);
		$query_search = $query. " ORDER BY PrimerApellido";
	}
		
		
	//ECHO $query_search;
		
        $consulta1=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
			 
	$row1 = mysql_fetch_array($consulta1);?>
 	<table width="100%" border="0"  align='center'>
		<tr>
			<td colspan="7" align="center"><h3><strong>REPORTE DE SOLICITUDES POR SUB-SERVICIO
			</h3></strong></td>
		</tr>
		<tr>
			<td colspan="7" align="center"><h3><strong><?php echo $row1['procedencia']; ?></strong></h3></td>
			</td>
		</tr>
		
	</table>
 	 <?php 
         $consulta=$objdatos->BuscarSolicitudesEspecialidad($query_search); ?>
 	<table width="100%" border="1" align="center" cellspacing="0">
		<tr>
			<td width="10%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>Fecha Solicitud</h4></strong></td>
			<td width="6%" class="StormyWeatherDataTD" style="color:#000000; font:bold" ><h4><strong>NEC </strong><h4></td>
			<td width="20%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Nombre Paciente</strong><h4></td>
			<td width="18%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>M&eacute;dico</strong><h4></td>
			<td width="12%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Origen</strong><h4></td>
			<td width="12%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Procedencia</strong><h4></td>
			<td width="15%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Establecimiento</strong><h4></td>
			<td width="15%" class="StormyWeatherDataTD" style="color:#000000; font:bold"><h4><strong>Estado Solicitud</strong><h4></td>
		</tr>    
	<?php $pos=0;
    	while ($row = mysql_fetch_array($consulta))
	{ ?>
		<tr>
			<td width="10%"><?php echo $row['FechaSolicitud']; ?></td>
			<td width="6%"><?php echo $row['IdNumeroExp'];?></td>
			<td width="20%"><?php echo $row['NombrePaciente'];?></td>
			<td width="18%"><?php echo $row['medico'];?></td>
			<td width="12%"><?php echo $row['origen'];?></td>
			<td width="12%"><?php echo $row['procedencia'];?></td>
			<td width="15%"><?php echo $row['Nombre'];?></td>
			<td width="15%"><?php echo $row['Estado'];?></td>
		</tr>
	<?php
		$pos=$pos + 1;
	}
		mysql_free_result($consulta);
	?>
	
  		<input type="hidden" name="oculto" id="text" value='".$pos."' /> 
   
	</table>
    	<table width="90%" border="0" align="center">
	<tr>
		<td colspan="7" align="center">	
			<div id="boton">	
				<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
				<input type="button" name="btncl" id="btnct" value="Regresar" onClick="window.close();"> </div>
				</div>
		</td>
	</tr>
	</table>
</body>
</html>
